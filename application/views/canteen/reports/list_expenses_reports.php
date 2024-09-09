<!DOCTYPE html>
<html>
<head>
<title></title>
<style>
  /* *{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
  page {
    background: white;
    display: block;
    margin: 1em auto 0;
    margin-bottom: 0.5cm;
    box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
  }
  page[size="A4"] {  
    width: 21cm;
    min-height: 29.7cm;
    padding: 3em;
    font-size: 13px;
    float: left;
    
  }
  @page {
    size: auto;   
    margin: 0;  
} */

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
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Expense Report</span></td>
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
    <table>
    

    <div style="float:left; margin-top:10px; width:100%;border:1px solid #111;border-top:none;font-size:13px;">       
        
          <div style="float:left;width:100%;border:1px solid #111;border-left:none;border-right:none;font-size:13px;">
            <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Date</u></div>
            <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Exp. Type</u></div> 
            <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Exp. Category</u></div> 
            <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Payment Mode</u></div>
            <div style="float:left;width:20%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div>
          </div>
         

     <?php //echo "<pre>"; print_r($all_expense_list);
     if(!empty($all_expense_list['expense_list']))
     {
        $i=1;
        $total = 0;
        $cash_total = 0;
        $cheque_total = 0;
        $neft_total = 0;
        $card_total = 0;
        
        foreach($all_expense_list['expense_list'] as $expenses)
        {
          ?> 
          <div style="float:left;width:100%; min-height: 35px;  margin-bottom: 3px;">
              <div style="float:left;width:10%;padding:1px 4px;"><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></div>
              <div style="float:left;width:20%;padding:1px 4px;"><?php echo $expenses->type; ?></div> 
              <div style="float:left;width:20%;padding:1px 4px;"><?php echo $expenses->exp_category; 
                  if(!empty(trim($expenses->remarks)))
                   {
                      echo ' ('.$expenses->remarks.')';
                   }
              ?></div>
              <div style="float:left;width:15%;padding:1px 4px;">
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
      }


      $p_amount=0;  
      foreach($all_expense_list['expense_payment_mode'] as $payment_mode) 
      {
        ?>
          <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:20%; font-weight: bold;">
              <span style="float:left;"><?php echo $payment_mode->payment_mode; ?>:</span>
              <span style="float:right;"><?php echo number_format($payment_mode->paid_total_amount,2); ?></span>
            </div>
          </div> 

      <?php 
      $p_amount = $p_amount+$payment_mode->paid_total_amount;
      } 
      ?>    

          

          <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:20%; font-weight: bold;">
              <span style="float:left;">Total:</span>
              <span style="float:right;"><?php echo number_format($p_amount,2); ?></span>
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
