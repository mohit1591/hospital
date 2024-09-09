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
    box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
  }
  page[size="A4"] {  
    width: 21cm;
    height: 29.7cm;  
    padding: 3em;
    font-size:13px;
  }
</style>
</head>

<body id="expenses" style="font:13px Arial;">
<page size="A4">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Expense Report</span></td>
      </tr>
      <tr>
        <td style="text-align:center;font-size:13px;padding:1em;">
          <strong>From</strong>
          <span><?php echo $get['start_date']; ?></span>
          <strong>To</strong>
          <span><?php echo $get['end_date']; ?></span>
        </td>
      </tr>
    <table>
    

    <div style="float:left; margin-top:10px; width:100%;border:1px solid #111;border-top:none;font-size:13px;">       
        
          <div style="float:left;width:100%;border:1px solid #111;border-left:none;border-right:none;font-size:13px;">
            <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Date</u></div>
            <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Voucher No</u></div>
            <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Exp. Type</u></div> 
            <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Exp. Category</u></div> 
            <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Payment Mode</u></div>
            <div style="float:left;width:20%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div>
          </div>
         

     <?php //echo "<pre>"; print_r($expense_list);
     if(!empty($expense_list))
     {
          

        $i=1;
        $total = 0;
        $cash_total = 0;
        $cheque_total = 0;
        $neft_total = 0;
        $card_total = 0;
        $mode = array('0'=>'Case','1'=>'Card','2'=>'Cheque','3'=>'NEFT');
        foreach($branch_gst_list as $expenses)
        {
          ?> 
          <div style="float:left;width:100%; height: 35px;">
              <div style="float:left;width:15%;padding:1px 4px;text-indent:15px;"><?php echo date('d-m-Y',strtotime($expenses->created_date)); ?></div>
              <div style="float:left;width:15%;padding:1px 4px;"><?php echo $expenses->purchase_no; ?>  </div>
              <div style="float:left;width:15%;padding:1px 4px;"><?php echo $expenses->invoice_no; ?></div> 
              <div style="float:left;width:15%;padding:1px 4px;"><?php //echo $expenses->remarks; ?></div>
              <div style="float:left;width:20%;padding:1px 4px;">
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
              </div>
              <div style="float:left;width:20%;padding:1px 4px;text-align:right;"><?php echo number_format($expenses->paid_amount,2); ?></div>
            </div> 


            

          <?php
          $i++;
        if($expenses->payment_mode=='cash')
        {
            $cash_total = $cash_total+$expenses->paid_amount;
        }
        if($expenses->payment_mode=='cheque')
        {
            $cheque_total = $cheque_total+$expenses->paid_amount;
        }
        if($expenses->payment_mode=='neft')
        {
            $neft_total = $neft_total+$expenses->paid_amount;
        }
        if($expenses->payment_mode=='card')
        {
            $card_total = $card_total+$expenses->paid_amount;
        }
          
          $total = $total+$expenses->paid_amount;
        }

        ?>
          
          <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:20%; font-weight: bold;">
              <span style="float:left;">Cash:</span>
              <span style="float:right;"><?php echo number_format($cash_total,2); ?></span>
            </div>
          </div> 

          <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:20%; font-weight: bold;">
              <span style="float:left;">Cheque:</span>
              <span style="float:right;"><?php echo number_format($cheque_total,2); ?></span>
            </div>
          </div> 

          <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:20%; font-weight: bold;">
              <span style="float:left;">NEFT:</span>
              <span style="float:right;"><?php echo number_format($neft_total,2); ?></span>
            </div>
          </div> 

          <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:20%; font-weight: bold;">
              <span style="float:left;">Card:</span>
              <span style="float:right;"><?php echo number_format($card_total,2); ?></span>
            </div>
          </div> 

          <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:20%; font-weight: bold;">
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
    </div>        
</page>

</body>
</html>