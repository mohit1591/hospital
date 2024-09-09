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
<page size="A4">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Doctor Wise Collection Report</span></td>
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
    
   

    <div style="float:left; margin-top:10px; width:100%;border:1px solid #111;border-top:none;font-size:13px;">       
        
          <div style="float:left;width:100%;border:1px solid #111;border-left:none;border-right:none;font-size:13px;">
            <div style="float:left;width:5%;font-weight:600;padding:4px;"><u>S.No.</u></div>
            <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Date</u></div>
            <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Booking Code.</u></div>
            <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Module Type</u></div> 
            <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Patient Name</u></div> 
            <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Patient Code</u></div> 
            <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Payment Mode</u></div>
            <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div>
          </div>
       <?php 
     if(!empty($list))
     {
        
        $total = 0;
       
        foreach ($doctor_list as $key => $doctor) { $doc_total = 0; $i=1;  ?>
         <p style="margin-bottom: 10px;"> <b><?php echo $doctor->doctor_name;?></b></p>

        <?php  foreach($list['record'] as $value)
        { 
          if($doctor->doctor_id== $value->doctor_id)
          {
          ?> 
          <div style="float:left;width:100%; min-height: 20px;  margin-bottom: 3px;">
             <div style="float:left;width:5%;padding:1px 2px;"><?php echo $i; ?></div>
              <div style="float:left;width:15%;padding:1px 2px;"><?php echo date('d-m-Y',strtotime($value->created_date)); ?></div>
              <div style="float:left;width:15%;padding:1px 2px;"><?php echo $value->booking_code; ?>  </div>
              <div style="float:left;width:10%;padding:1px 2px;"><?php echo $value->module; ?></div> 
              <div style="float:left;width:15%;padding:1px 2px;"><?php echo $value->patient_name;  ?></div>
              <div style="float:left;width:15%;padding:1px 2px;">
              <?php echo ucwords($value->patient_code); ?>      
              </div>
              <div style="float:left;width:10%;padding:1px 2px;">
              <?php echo ucwords($value->mode); ?>      
              </div>
              <div style="float:left;width:15%;padding:1px 2px;text-align:right;"><?php  $total = $total+$value->debit; $doc_total=$doc_total+$value->debit; echo number_format($value->debit,2); ?></div>
            </div> 
    <?php $i++;
          } } ?>
           <div style="float:left;width:100%;padding:2px;text-align:right;">            
            <div style="float:right;width:20%; font-weight: bold;">
              <span style="float:left;">Total:</span>
              <span style="float:right;"><?php echo number_format($doc_total,2); ?></span>
            </div>
          </div>
     <?php }?>
       <hr>
     <?php $p_amount=0;  
      foreach($list['pay_mode'] as $payment_mode) 
      {
        ?>
          <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:20%; font-weight: bold;">
              <span style="float:left;"><?php echo $payment_mode->mode; ?>:</span>
              <span style="float:right;"><?php echo number_format($payment_mode->paid_total_amount,2); ?></span>
            </div>
          </div> 

      <?php 
      $p_amount = $p_amount+$payment_mode->paid_total_amount;
      } 
      ?>    

          

          <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:25%; font-weight: bold;">
              <span style="float:left;">Grand Total:</span>
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
