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
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Ledger Report</span></td>
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
            <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Date</u></div>
            <div style="float:left;width:35%;font-weight:600;padding:4px;"><u>Bill No.</u></div> 
            <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Mode</u></div> 
            <div style="float:left;width:13%;font-weight:600;padding:4px;"><u>Credit</u></div>
            <div style="float:left;width:12%;font-weight:600;padding:4px;"><u>Debit</u></div>
           <!-- <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Balance</u></div>-->
          </div>
     <?php if(!empty($report_list))
     {
        $i=1;
        $credit_total = 0;
        $debit_total = 0;
        $blns_total = 0;
//print_r($report_list);die();
        foreach($report_list as $list)
        {  ?>
       <div style="float:left;width:100%;border:1px solid #111;border-left:none;border-right:none;font-size:13px;">
            <div style="float:left;width:5%;font-weight:600;padding:4px;"><?php echo $i;?></div>
            <div style="float:left;width:20%;font-weight:600;padding:4px;"><?php if($list->c_date !='0000-00-00 00:00:00'){ echo date('d-m-Y h:i A',strtotime($list->c_date));}?></div>
            <div style="float:left;width:35%;font-weight:600;padding:4px;"><?php echo $list->bill_no;?></div> 
            <div style="float:left;width:15%;font-weight:600;padding:4px;"><?php echo $list->payment_mode;?></div> 
            <div style="float:left;width:13%;font-weight:600;padding:4px;"><?php echo number_format($list->blnce,2);$credit_total=$credit_total+$list->blnce;?></div>
            <div style="float:left;width:12%;font-weight:600;padding:4px;"><?php echo number_format($list->paid_amount,2);$debit_total=$debit_total+$list->paid_amount;?></div>
            <!--<div style="float:left;width:15%;font-weight:600;padding:4px;"><?php echo number_format($list->balance,2);$blns_total=$blns_total+$list->balance;?></div>-->
          </div>  
          <?php $i++; } ?>

          <div style="float:left;width:100%;border:1px solid #111;border-left:none;border-right:none;font-size:13px;">
            <div style="float:left;width:5%;font-weight:600;padding:4px;"></div>
            <div style="float:left;width:20%;font-weight:600;padding:4px;"></div>
            <div style="float:left;width:35%;font-weight:600;padding:4px;"><b>Total</b></div> 
            <div style="float:left;width:15%;font-weight:600;padding:4px;"></div> 
            <div style="float:left;width:13%;font-weight:600;padding:4px;"><?php echo number_format($credit_total,2);?></div>
            <div style="float:left;width:12%;font-weight:600;padding:4px;"><?php echo number_format($debit_total,2);?></div>
           <!-- <div style="float:left;width:15%;font-weight:600;padding:4px;"><?php echo number_format($credit_total-$debit_total,2);?></div>-->
          </div>  
         <?php }else{ ?>  
             <div style="text-align: center; width:100%;font-size:13px;">No records found...</div>
          <?php } ?>  
          <table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collpase;font-size:13px;text-align:center;">
                 <thead>
                   <tr>
                     <th>Credit</th>
                     <th>Debit</th>
                     <th>Balance</th>
                   </tr>
                 </thead>
                 <tbody>
                   <tr>
                     <td><?php echo number_format($credit_total,2);?></td>
                     <td><?php echo number_format($debit_total,2);?></td>
                     <td><?php echo number_format($credit_total-$debit_total,2);?></td>
                   </tr>
                 </tbody>
                </table>
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
