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
                  
width: 21cm;      
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

<!--<body id="expenses" style="font:13px Arial;"> -->
<body id="expenses" style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">
  <?php  $users_data = $this->session->userdata('auth_users'); 
$get=$_GET;
$opd_coll=0;
$opd_ret_exp=0;
$bill_coll=0;
$bill_ref_exp=0;
$ipd_coll=0;
$ipd_ref_exp=0;
$path_coll=0;
$pat_ret_exp=0;
$ot_coll=0;
$ot_ref_exp=0;
$amb_coll=0;
$amb_ref_exp=0;
$day_coll=0;
$day_care_ref_exp=0;
$blood_coll=0;
$bb_ref_exp=0;
$pur_st_inv=0;
$sale_coll=0;
$purret_coll=0;
$med_pur_exp=0;
$med_sale_exp=0;
$vacc_coll=0;
$vacc_pur_exp=0;
$vacc_sale_ret_exp=0;
?>

<page size="A4">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">MOdule Wise Profit/Loss Report</span></td>
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

  if(!empty($self_opd_collection_list['self_opd_coll']) || !empty($self_day_care_collection_list['self_day_care_coll']) || !empty($self_purchase_return_collection_list['self_purchase_coll']) || !empty($self_billing_collection_list['self_bill_coll']) || !empty($self_medicine_collection_list['med_coll']) || !empty($self_ipd_collection_list['ipd_coll']) || !empty($self_medicine_return_collection_list) || !empty($pathology_self_collection_list['path_coll']) || !empty($self_ot_collection_list['self_ot_coll']) || !empty($self_vaccination_collection_list['vaccine_coll']) || !empty($self_blood_bank_collection_list['self_blood_bank_collection']) || !empty($self_ambulance_collection_list['self_ambulance_coll']))
  {

    ?>
    <div style="float:left;width:100%;border:1px solid #111;">
      <div style="float:left; width:100%;font-size:10px;">        
        <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;">
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
         </table>

        <?php 
           
          if(!empty($self_opd_collection_list['self_opd_coll']) && $get['module']=='7' || $get['module']=='' )
          {
           ?>
           <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;">
           <tbody>
            <tr>
              <td style="padding:5px 0"><u>Branch: Self</u></td>
            </tr>

            <tr>
              <td style="padding:5px 0"><u>OPD</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $opd_coll=0;
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
          </table>

        

          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
              foreach($self_opd_collection_list['self_opd_coll_payment_mode'] as $payment_mode_opd){ ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $payment_mode_opd->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_opd->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>



    <?php  $opd_coll=$self_total;
     $grand_collection_total = $grand_collection_total+$self_total;
  } ?>
<!-- opd collection -->

<!-- Day Care collection -->

        <?php 
           
          if(!empty($self_day_care_collection_list['self_day_care_coll']) && $get['module']=='15' || $get['module']=='')
          {
           ?>
           <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;">
           <tbody>
            <tr>
              <td style="padding:5px 0"><u> Day_Care </u></td>
            </tr>
            <?php 
            $k = 1 ;
            $day_coll=0;
            $self_total = 0;
            $self_counter = count($self_day_care_collection_list['self_day_care_coll']);
            foreach($self_day_care_collection_list['self_day_care_coll'] as $collection)
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
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0'){?><td><?php echo $collection->debit; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0'){ ?><td><?php echo $collection->discount_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0'){ ?><td><?php echo $collection->debit; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0'){ ?><td><?php echo $collection->debit; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0'){ ?><td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0'){ ?><td><?php echo $collection->mode; ?></td><?php }              
              

              } ?>
              
            </tr>
           
        <?php $k++;
        $self_total = $self_total+$collection->debit;
     } ?>


           </tbody>
          </table>

        

          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
              foreach($self_day_care_collection_list['self_day_care_coll_payment_mode'] as $payment_mode_day_care){ ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $payment_mode_day_care->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_day_care->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>



    <?php  $day_coll=$self_total;
     $grand_collection_total = $grand_collection_total+$self_total;
  } ?>
<!-- Day Care collection -->





<?php
          if(!empty($self_inven_pur_ret_collection_list['invent_pur_ret_coll']) && $get['module']=='4' || $get['module']=='')
          {?>
           <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;">
           <tbody>
            <tr>
              <td style="padding:5px 0"><u>Inventory Purchase return</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $stock_return=0;
            $self_total = 0;
            $self_counter = count($self_inven_pur_ret_collection_list['invent_pur_ret_coll']);
            foreach($self_inven_pur_ret_collection_list['invent_pur_ret_coll'] as $collection)
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
          </table>

        

          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
              foreach($self_inven_pur_ret_collection_list['invent_pur_ret_pay_mode'] as $payment_mode_opd){ ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $payment_mode_opd->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_opd->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>
              <?php $stock_return=$self_total; $grand_collection_total = $grand_collection_total+$self_total;} ?> 





          <?php
          if(!empty($self_purchase_return_collection_list['self_purchase_coll']) && $get['module']=='2' || $get['module']=='')
          {?>
           <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;">
           <tbody>
            <tr>
              <td style="padding:5px 0"><u>Purchase return</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $purret_coll=0;
            $self_total = 0;
            $self_counter = count($self_purchase_return_collection_list['self_purchase_coll']);
            foreach($self_purchase_return_collection_list['self_purchase_coll'] as $collection)
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
          </table>

        

          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
              foreach($self_purchase_return_collection_list['purchase_payment_mode_array'] as $payment_mode_opd){ ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $payment_mode_opd->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_opd->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>
              <?php $purret_coll=$self_total; $grand_collection_total = $grand_collection_total+$self_total;} ?> 

              <!--blood bank colllection -->
          

       <?php 
           
         if(!empty($self_blood_bank_collection_list['self_blood_bank_collection']) && $get['module']=='12' || $get['module']=='')
         {
           ?>
           <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;"> 
           <tbody>
           
            <tr>
              <td style="padding:5px 0"><u>Blood Bank</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $blood_coll=0;
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
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
              $p_m_k=1;
                  foreach($self_blood_bank_collection_list['self_blood_bank_coll_payment_mode'] as $self_payment_mode) { ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $self_payment_mode->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($self_payment_mode->tot_debit,2); echo $p_m_k; ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>
<?php  $blood_coll=$self_total;
$grand_collection_total = $grand_collection_total+$self_total;
} ?>
          <!--BLOOD bank collection -->
          <!-- IPD -->
              


       <?php 
           
         if(!empty($self_ipd_collection_list['ipd_coll']) && $get['module']=='10' || $get['module']=='')
          {
           ?>
           <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;"> 
           <tbody>
           
            <tr>
              <td style="padding:5px 0"><u>IPD</u></td>
            </tr>

            
            <?php 
           $k = 1 ;
            $ipd_coll=0;
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
                ?><td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></td><?php
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
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
               $p_m_k=1;
               foreach($self_ipd_collection_list['ipd_coll_payment_mode'] as $ipd_pay_mode)
               { ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $ipd_pay_mode->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($ipd_pay_mode->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

        <?php $ipd_coll=$self_total;
        $grand_collection_total = $grand_collection_total+$self_total;
      } ?>

              <!-- ipd end -->
           
           <!-- self opd billing collection -->
           
        <?php 
           //echo "<pre>"; print_r($self_billing_collection_list['self_bill_coll']); exit;
         if(!empty($self_billing_collection_list['self_bill_coll']) && $get['module']=='14' || $get['module']=='')
         {
           ?>
      
       <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;"> 
           <tbody>
           
            <tr>
              <td style="padding:5px 0"><u>Billing</u></td>
            </tr>

            
            <?php 
            $l = 1 ;
            $bill_coll=0;
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
           
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
               
                $p_m_k=1;
                foreach($self_billing_collection_list['self_bill_coll_payment_mode'] as $billing_payment_mode){ ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $billing_payment_mode->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($billing_payment_mode->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_billing_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>
           <?php  $bill_coll=$self_billing_total;
           $grand_collection_total = $grand_collection_total+$self_billing_total;
         } ?>    
           <!-- end self opd billing collection -->
        <!-- self medicine collection -->
          <?php
  if(!empty($self_medicine_collection_list['med_coll']) && $get['module']=='2' || $get['module']=='' )
  { ?>
        <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;"> 
          <tbody>
           
            <tr>
              <td style="padding:5px 0"><u>Medicine Sale</u></td>
            </tr>

            
            <?php 
            $m = 1 ;
            $sale_coll=0;
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
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
               
                $p_m_k=1;
                      foreach($self_medicine_collection_list['med_coll_pay_mode'] as $self_medicine_pay){ ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $self_medicine_pay->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($self_medicine_pay->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_med_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>
           <?php $sale_coll=$self_med_total;
           $grand_collection_total = $grand_collection_total+$self_med_total;
         } ?>
        <!-- end self medicine collection -->
       <!--pathology self collection -->
             
        <?php 
           
          if(!empty($pathology_self_collection_list['path_coll']) && $get['module']=='8' || $get['module']=='')
          {
           ?>
           <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;"> 
          <tbody>
            <tr>
              <td style="padding:5px 0"><u>Pathology</u></td>
            </tr>

            
            <?php 
            $path_coll=0;
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
                ?><td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></td><?php
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
 
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
              foreach($pathology_self_collection_list['path_coll_pay_mode'] as $payment_mode_path){ ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $payment_mode_path->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_path->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

         <?php $path_coll=$self_total;
         $grand_collection_total = $grand_collection_total+$self_total;
       } ?>

    <!---pathology end -->

    <!-- vaccination -->
   <?php 
        if(!empty($self_vaccination_pur_ret_collection_list['invent_pur_ret_coll']) && $get['module']=='5' || $get['module']=='')
        { 
          $data_empty = 1;
        ?>

         <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;"> 
         <tbody>
            <tr>
              <td style="padding:5px 0"><u>Vaccination Purchase Return</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $vacc_pur_ret_coll=0;
            $self_vaccine_total = 0;
            $self_counter = count($self_vaccination_pur_ret_collection_list['invent_pur_ret_coll']);
            foreach($self_vaccination_pur_ret_collection_list['invent_pur_ret_coll'] as $vaccination_collection)
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
    </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
              foreach($self_vaccination_pur_ret_collection_list['invent_pur_ret_pay_mode'] as $vaccine_payment_mode){ ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $vaccine_payment_mode->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($vaccine_payment_mode->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_vaccine_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

         <?php $vacc_pur_ret_coll=$self_vaccine_total;
         $grand_collection_total = $grand_collection_total+$self_vaccine_total;
       } ?>
          <!-- end vaccination -->
    <?php 
        if(!empty($self_vaccination_collection_list['vaccine_coll']) && $get['module']=='5' || $get['module']=='')
        { 
          $data_empty = 1;
        ?>

         <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;"> 
         <tbody>
            <tr>
              <td style="padding:5px 0"><u>Vaccination Sale</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $vacc_coll=0;
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
    </table>
          <footer style="float:left;width:100%">
            
              <?php
              //echo $self_vaccine_total; exit;
              //echo "<pre>"; print_r($self_vaccination_collection_list['vaccine_coll_payment_mode']); exit;
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
              foreach($self_vaccination_collection_list['vaccine_coll_payment_mode'] as $vaccine_payment_mode){ ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $vaccine_payment_mode->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($vaccine_payment_mode->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_vaccine_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

         <?php $vacc_coll=$self_vaccine_total;
         $grand_collection_total = $grand_collection_total+$self_vaccine_total;
       } ?>
         
                      
                      <!-- end vaccination -->

                       <!--ot colllection -->
          <?php 
           
         if(!empty($self_ot_collection_list['self_ot_coll']) && $get['module']=='11' || $get['module']=='')
          {
           ?>
           <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;">
            <tbody>
            <tr>
              <td style="padding:5px 0"><u>OT</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $ot_coll=0;
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
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
              $p_m_k=1;
                foreach($self_ot_collection_list['self_ot_coll_payment_mode'] as $self_payment_mode) { ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;padding-right:1rem;"><?php echo $self_payment_mode->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($self_payment_mode->tot_debit,2);?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>
<?php  $ot_coll=$self_total;
$grand_collection_total = $grand_collection_total+$self_total;
}  ?>
       <!--ot collection -->
       <!--- Self Ambulance -->

        <?php
          if(!empty($self_ambulance_collection_list['self_ambulance_coll']) && $get['module']=='13' || $get['module']=='')
          {
           ?>
           <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;">
           <tbody>
            <tr>
              <td style="padding:5px 0"><u>Ambulance</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $amb_coll=0;
            $self_total = 0;
            $self_counter = count($self_ambulance_collection_list['self_ambulance_coll']);
            foreach($self_ambulance_collection_list['self_ambulance_coll'] as $collection)
            { 
            
            ?>
            <tr>
           <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0'){?><td width="10" align="center"><?php echo $k; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0'){ ?><td style="widows: 20px;"><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></td><?php }
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
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:100px;text-align: right;">
              <table style="font-size:10px;text-align: right;">
              <?php 
              foreach($self_ambulance_collection_list['self_ambulance_coll_payment_mode'] as $payment_mode_opd){ ?>
              <div style="float:left;width:100%;padding:4px;text-align:right;">
                <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                  <span style="float:left;"><?php echo $payment_mode_opd->mode; ?></span>
                  <span style="float:right;"><?php echo number_format($payment_mode_opd->tot_debit,2); ?></span>
                </div>
              </div> 
              <?php $p_m_k++;} ?>
              <tr>
                <td>
                  <strong style="float:left;padding-right:1rem;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>
    <?php $amb_coll=$self_total;
     $grand_collection_total = $grand_collection_total+$self_total;
  }
            ?>
       <!--- Self Ambulance -->
       <!-- over all collection -->

       <footer style="float:left;width:100%">
        <div style="float: right;width:130px;text-align: right;padding-right: 10px;">
          <table style="font-size:10px;text-align: right;">
            <tr>
              <td  style="float:left;border-top:1px solid black;" >
                <strong style="float:left;padding-right:1rem;">Grand Total:</strong>
                <span style="float:right;"><?php echo number_format($grand_collection_total,2);?></span>
              </td>
            </tr>
          </table>
        </div>

      </footer>
       <!-- end of overall collection -->
              </div>
          </div>
           <?php 
         }
    ?>  
      

  <h4>Expense List</h4>
    <div style="float:left; margin-top:10px; width:100%;border:1px solid #111;border-top:none;">
          <table style="width:100%;font-size:10px;font-family: arial;">
            <tbody>
              <tr>
              <th><u>Date</u></th>
              <th><u>Voucher No.</u></th>
              <th><u>Exp. Type</u></th> 
              <th><u>Exp. Category</u></th> 
              <th><u>Payment Mode</u></th>
              <th><u>Amount</u></th>
            </tr>
     <?php 

     if(!empty($normal_expense_list['expense_list']) || !empty($salary_list['expense_list']) || !empty($med_pur_list['expense_list']) || !empty($sale_ret_list['sale_ret_list']) || !empty($stock_list['expense_list']) || !empty($vacc_pur_list['expense_list']) || !empty($vacc_sale_ret_list['expense_list']) || !empty($opd_refund_list['expense_list']) || !empty($path_refund_list['expense_list']) || !empty($med_refund_list['expense_list']) || !empty($ipd_refund_list['expense_list']) || !empty($ot_refund_list['expense_list']) || !empty($bb_refund_list['expense_list']) || !empty($ambulance_list['expense_list']) || !empty($daycare_list['expense_list']) || !empty($doc_com_list['doctor_commission'])){
        $i=1;
        $grand_tot = 0;
     if(!empty($normal_expense_list['expense_list']) && $get['module']=='0' || $get['module']=='')
     { ?>
            <tr>
              <td style="padding:5px 0"><u>Expenses</u></td>
            </tr>
       <?php $i=1;
        $nor_exp=0;
        $exp_totals = 0;
        foreach($normal_expense_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_totals = $exp_totals+$expenses->paid_amount;
      $i++;
      }
      ?>
    
              <?php 
               foreach($normal_expense_list['expense_payment_mode'] as $payment_mode) 
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
                  <span><?php echo number_format($exp_totals,2); ?></span>
                </td>
              </tr>
    <?php $nor_exp=$exp_totals; $grand_tot = $grand_tot+$exp_totals; }

     if(!empty($salary_list['expense_list']))
     { ?>
             <tr>
              <td style="padding:5px 0"><u>Employee Salary</u></td>
            </tr>
        <?php  $i=1;
        $sal_exp=0;
        $exp_total = 0;
        foreach($salary_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($salary_list['expense_payment_mode'] as $payment_mode) 
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
    <?php $sal_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; }

     if(!empty($med_pur_list['expense_list']) && $get['module']=='2' || $get['module']=='')
     { ?>
       <tr>
        <td style="padding:5px 0"><u>Medicine Purchase</u></td>
       </tr>
      <?php
        $i=1;
        $med_pur_exp=0;
        $exp_totals = 0;
        foreach($med_pur_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_totals = $exp_totals+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($med_pur_list['expense_payment_mode'] as $payment_mode) 
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
                  <span><?php echo number_format($exp_totals,2); ?></span>
                </td>
              </tr>
    <?php $med_pur_exp=$exp_totals; $grand_tot = $grand_tot+$exp_totals; }

    if(!empty($sale_ret_list['sale_ret_list']) && $get['module']=='2' || $get['module']=='')
     { ?>
       <tr>
        <td style="padding:5px 0"><u>Medicine Sale Return</u></td>
       </tr>
      <?php
        $i=1;
        $med_sale_exp=0;
        $exp_total = 0;
        foreach($sale_ret_list['sale_ret_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($sale_ret_list['expense_payment_mode'] as $payment_mode) 
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
    <?php $med_sale_exp=$exp_total;  $grand_tot = $grand_tot+$exp_total; }


if(!empty($stock_list['expense_list']) && $get['module']=='4' || $get['module']=='')
     {
      ?>
       <tr>
        <td style="padding:5px 0"><u>Purchase Stock Inventory</u></td>
       </tr>
      <?php
        $i=1;
        $pur_st_inv=0;
        $exp_total = 0;
        foreach($stock_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($stock_list['expense_payment_mode'] as $payment_mode) 
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
    <?php $pur_st_inv=$exp_total; $grand_tot = $grand_tot+$exp_total; }


if(!empty($vacc_pur_list['expense_list']) && $get['module']=='5' || $get['module']=='')
     { ?>
       <tr>
        <td style="padding:5px 0"><u>Vaccine Purchase</u></td>
       </tr>
      <?php

        $i=1;
        $vacc_pur_exp=0;
        $exp_total = 0;
        foreach($vacc_pur_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>

              <?php 
               foreach($vacc_pur_list['expense_payment_mode'] as $payment_mode) 
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
    <?php $vacc_pur_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; }


if(!empty($vacc_sale_ret_list['expense_list']) && $get['module']=='5' || $get['module']=='')
     {?>
       <tr>
        <td style="padding:5px 0"><u>Vaccine Sale Return</u></td>
       </tr>
      <?php
        $i=1;
        $vacc_sale_ret_exp=0;
        $exp_total = 0;
        foreach($vacc_sale_ret_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($vacc_sale_ret_list['expense_payment_mode'] as $payment_mode) 
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
    <?php $vacc_sale_ret_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; }


if(!empty($opd_refund_list['expense_list']) && $get['module']=='7' || $get['module']=='' )
     { ?>
       <tr>
        <td style="padding:5px 0"><u>OPD Return</u></td>
       </tr>
      <?php
        $i=1;
        $opd_ret_exp=0;
        $exp_total = 0;
        foreach($opd_refund_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($opd_refund_list['expense_payment_mode'] as $payment_mode) 
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
    <?php $opd_ret_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; }


if(!empty($path_refund_list['expense_list']) && $get['module']=='8' || $get['module']=='')
     {?>
       <tr>
        <td style="padding:5px 0"><u>Pathology Return</u></td>
       </tr>
      <?php
        $i=1;
        $pat_ret_exp=0;
        $exp_total = 0;
        foreach($path_refund_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($path_refund_list['expense_payment_mode'] as $payment_mode) 
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
    <?php $pat_ret_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; }

if(!empty($med_refund_list['expense_list']) && $get['module']=='2' || $get['module']=='' )
     {?>
       <tr>
        <td style="padding:5px 0"><u>Medicine Refund</u></td>
       </tr>
      <?php
        $i=1;
        $med_ret_exp=0;
        $exp_total = 0;
        foreach($med_refund_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($med_refund_list['expense_payment_mode'] as $payment_mode) 
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
    <?php $med_ret_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; }


if(!empty($ipd_refund_list['expense_list']) && $get['module']=='10' || $get['module']=='')
     {?>
       <tr>
        <td style="padding:5px 0"><u>IPD Refund</u></td>
       </tr>
      <?php
        $i=1;
        $ipd_ref_exp=0;
        $exp_total = 0;
        foreach($ipd_refund_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($ipd_refund_list['expense_payment_mode'] as $payment_mode) 
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
    <?php $ipd_ref_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; }


if(!empty($ot_refund_list['expense_list']) && $get['module']=='11' || $get['module']=='')
     {?>
       <tr>
        <td style="padding:5px 0"><u>OT Refund</u></td>
       </tr>
      <?php
        $i=1;
        $ot_ref_exp=0;
        $exp_total = 0;
        foreach($ot_refund_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($ot_refund_list['expense_payment_mode'] as $payment_mode) 
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
    <?php $ot_ref_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; }


if(!empty($bb_refund_list['expense_list']) && $get['module']=='12' || $get['module']=='')
     {?>
       <tr>
        <td style="padding:5px 0"><u>Blood Bank Refund</u></td>
       </tr>
      <?php
        $i=1;
        $bb_ref_exp=0;
        $exp_total = 0;
        foreach($bb_refund_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
              <?php 
               foreach($bb_refund_list['expense_payment_mode'] as $payment_mode) 
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
              <?php $bb_ref_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; }


if(!empty($ambulance_list['expense_list']) && $get['module']=='13' || $get['module']=='')
     {?>
       <tr>
        <td style="padding:5px 0"><u>Ambulance Refund</u></td>
       </tr>
      <?php
        $i=1;
        $amb_ref_exp=0;
        $exp_total = 0;
        foreach($ambulance_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
                  <?php 
                   foreach($ambulance_list['expense_payment_mode'] as $payment_mode) 
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

              if($opd_bill_list['expense_list'] && $get['module']=='14' || $get['module']=='')
              {
                ?>
                     <tr>
                      <td style="padding:5px 0"><u>OPD Billing Refund</u></td>
                     </tr>
                    <?php
                      $i=1;
                      $bill_ref_exp=0;
                      $exp_total = 0;
                      foreach($opd_bill_list['expense_list'] as $expenses)
                      {
                        ?> 
                        <tr>
                            <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                            <td><?php echo $expenses->vouchar_no; ?>  </td>
                            <td><?php echo $expenses->type; ?></td> 
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
                            <td><?php echo number_format($expenses->paid_amount,2); ?></td>
                          </tr> 
                     <?php
                     $exp_total = $exp_total+$expenses->paid_amount;
                    $i++;
                    }
                    ?>
                   
                            <?php 
                             foreach($opd_bill_list['expense_payment_mode'] as $payment_mode) 
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
                  <?php $bill_ref_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; 
              }

if(!empty($daycare_list['expense_list'])  && $get['module']=='15' || $get['module']=='')
     {?>
       <tr>
        <td style="padding:5px 0"><u>Day Care Refund</u></td>
       </tr>
      <?php
        $i=1;
        $day_care_ref_exp=0;
        $exp_total = 0;
        foreach($daycare_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td><?php echo $expenses->vouchar_no; ?>  </td>
              <td><?php echo $expenses->type; ?></td> 
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
              <td><?php echo number_format($expenses->paid_amount,2); ?></td>
            </tr> 
       <?php
       $exp_total = $exp_total+$expenses->paid_amount;
      $i++;
      }
      ?>
     
              <?php 
               foreach($daycare_list['expense_payment_mode'] as $payment_mode) 
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
    <?php  $day_care_ref_exp=$exp_total; $grand_tot = $grand_tot+$exp_total; }
    if(!empty($doc_com_list['doctor_commission']) && $get['module']=='16' || $get['module']=='')
   {?>
       <tr>
        <td style="padding:5px 0"><u>Doctor Commission </u></td>
       </tr>
      <?php
    $i=0;
    $doc_com_exp=0;
    $tot_exp=0;
        foreach($doc_com_list['doctor_commission'] as $expenses_doc)
        {
          ?> 
          <tr>
              <td><?php echo date('d-m-Y',strtotime($expenses_doc->created_date)); ?></td>
              <td><?php echo ''; ?>  </td>
              <td><?php echo 'Doctor Commission'; ?></td> 
              <td><?php echo 'N/A';?></td>
              <td>
              <?php echo ucwords(strtolower($expenses_doc->mode));   ?>      
              </td>
              <td><?php echo number_format($expenses_doc->debit,2); ?></td>
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
 <?php $doc_com_exp=$exp_total; $grand_tot = $grand_tot+$exp_total;
 } ?>
      </tbody>
    </table>
  <div style="float:left;width:100%;padding:4px;text-align:right;">            
    <div style="float:right;width:50%; font-weight: bold;">
      <span style="float:left;">Total Expenses:</span>
      <span style="float:right;"><?php echo number_format($grand_tot,2); ?></span>
    </div>
  </div>

    


    <?php } else{?>

      <table style="text-align: center; width: 100%;">
        <tr>
          <td style="float:left;width:100%;clear:both;padding:1px 4px;text-indent:15px;text-align:center;color:#990000;">Expense Record not found
          </td>
        </tr>
     </table>
     <?php }?>
</div>
   <table style="width:100%;font-family: arial; border: 1px solid; text-align:left;">
         <thead>
          <tr>
              <th> Module Name </th>
              <th> Collection </th>
              <th> Expenses </th>
              <th> Profit/Loss </th>
          </tr>
        </thead>
        <tbody style="font-size:10px;">
          <?php $coll_tot=0; $exp_tot=0; $pro_loss_tot=0; if($get['module']=='7' || $get['module']==''){?>
           <tr>
              <td>  OPD </td>
              <td> <?php $coll_tot += $opd_coll; echo $opd_coll; ?> </td>
              <td> <?php $exp_tot += $opd_ret_exp; echo $opd_ret_exp; ?> </td>
              <td> <?php $pro_loss_tot += ($opd_coll-$opd_ret_exp); echo $opd_coll-$opd_ret_exp; ?> </td>
          </tr>
        <?php } if($get['module']=='14' || $get['module']==''){  ?>
           <tr>
              <td>  OPD Bill </td>
              <td> <?php $coll_tot += $bill_coll; echo $bill_coll; ?> </td>
              <td> <?php $exp_tot += $bill_ref_exp; echo $bill_ref_exp; ?> </td>
              <td> <?php $pro_loss_tot += ($bill_coll-$bill_ref_exp); echo $bill_coll-$bill_ref_exp; ?> </td>
          </tr>
        <?php } if($get['module']=='10' || $get['module']==''){  ?>
          <tr>
              <td> IPD </td>
              <td> <?php $coll_tot += $ipd_coll; echo $ipd_coll; ?> </td>
              <td> <?php $exp_tot += $ipd_ref_exp; echo $ipd_ref_exp; ?> </td>
              <td> <?php $pro_loss_tot += ($ipd_coll-$ipd_ref_exp); echo $ipd_coll-$ipd_ref_exp; ?> </td>
          </tr>
          <?php } if($get['module']=='8' || $get['module']==''){  ?>
          <tr>
              <td> Pathology </td>
              <td> <?php $coll_tot += $path_coll; echo $path_coll; ?> </td>
              <td> <?php $exp_tot += $pat_ret_exp; echo $pat_ret_exp; ?> </td>
              <td> <?php $pro_loss_tot += ($path_coll-$pat_ret_exp); echo $path_coll-$pat_ret_exp; ?> </td>
          </tr>
        <?php } if($get['module']=='11' || $get['module']==''){  ?>
          <tr>
              <td> OT </td>
              <td> <?php $coll_tot += $ot_coll; echo $ot_coll; ?> </td>
              <td> <?php $exp_tot += $ot_ref_exp; echo $ot_ref_exp; ?> </td>
              <td> <?php $pro_loss_tot += ($ot_coll-$ot_ref_exp); echo $ot_coll-$ot_ref_exp; ?> </td>
          </tr>
      <?php } if($get['module']=='13' || $get['module']==''){  ?>
          <tr>
              <td> Ambulance </td>
              <td> <?php $coll_tot += $amb_coll; echo $amb_coll; ?> </td>
              <td> <?php $exp_tot += $amb_ref_exp; echo $amb_ref_exp; ?> </td>
              <td> <?php $pro_loss_tot += ($amb_coll-$amb_ref_exp); echo $amb_coll-$amb_ref_exp; ?> </td>
          </tr>
       <?php } if($get['module']=='15' || $get['module']==''){  ?>
          <tr>
              <td> Day Care </td>
              <td> <?php $coll_tot += $day_coll; echo $day_coll; ?> </td>
              <td> <?php $exp_tot += $day_care_ref_exp; echo $day_care_ref_exp; ?> </td>
              <td> <?php $pro_loss_tot += ($day_coll-$day_care_ref_exp); echo $day_coll-$day_care_ref_exp; ?> </td>
          </tr>
     <?php } if($get['module']=='12' || $get['module']==''){  ?>
           <tr>
              <td> Blood Bank </td>
              <td> <?php $coll_tot += $blood_coll; echo $blood_coll; ?> </td>
              <td> <?php $exp_tot += $bb_ref_exp; echo $bb_ref_exp; ?> </td>
              <td> <?php $pro_loss_tot += ($blood_coll-$bb_ref_exp); echo $blood_coll-$bb_ref_exp; ?> </td>
          </tr>
    <?php } if($get['module']=='4' || $get['module']==''){  ?>
          <tr>
              <td>  Stock Inventory </td>
              <td> <?php $coll_tot += $stock_return; echo $stock_return; ?> </td>
              <td> <?php $exp_tot += $pur_st_inv; echo $pur_st_inv; ?> </td>
              <td> <?php $pro_loss_tot += ($stock_return-$pur_st_inv); echo $stock_return-$pur_st_inv; ?> </td>
          </tr>
      <?php } if($get['module']=='5' || $get['module']==''){  ?>
          <tr>
              <td> Vaccination </td>
              <td> <?php $coll_tot +=$vacc_pur_ret_coll+$vacc_coll; echo $vacc_pur_ret_coll+$vacc_coll; ?> </td>
              <td> <?php $exp_tot += $vacc_pur_exp+$vacc_sale_ret_exp; echo $vacc_pur_exp+$vacc_sale_ret_exp; ?> </td>
              <td> <?php $pro_loss_tot += ($vacc_pur_ret_coll+$vacc_coll-$vacc_pur_exp-$vacc_sale_ret_exp); echo $vacc_pur_ret_coll+$vacc_coll-$vacc_pur_exp-$vacc_sale_ret_exp; ?> </td>
          </tr>
        <?php } if($get['module']=='2' || $get['module']==''){  ?> 
          <tr>
              <td> Medicine </td>
              <td> <?php $coll_tot += $sale_coll+$purret_coll; echo $sale_coll+$purret_coll; ?> </td>
              <td> <?php $exp_tot += $med_pur_exp+$med_sale_exp; echo $med_pur_exp+$med_sale_exp; ?> </td>
              <td> <?php $pro_loss_tot +=($sale_coll+$purret_coll-$med_pur_exp+$med_sale_exp); echo $sale_coll+$purret_coll-$med_pur_exp+$med_sale_exp; ?> </td>
          </tr>
        <?php }?>
         <tr style="border-top: 1px solid;">
              <th> Total </th>
              <th> <?php echo $coll_tot; ?> </th>
              <th> <?php echo $exp_tot; ?> </th>
              <th> <?php echo $pro_loss_tot; ?> </th>
          </tr>
      </tbody>
      </table> 
      </table> 
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
