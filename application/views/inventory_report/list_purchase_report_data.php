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
    min-height: 29.7cm !important;  
    padding: 3em;
    font-size:13px;
    overflow-y:auto;
  }
</style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

  <page size="A4">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Purchase Inventory Report</span></td>
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
    <!-- Branch list start -->

    <div style="float:left;width:100%;border:1px solid #111;font-size:13px;">
      <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Sr.No.</u></div>
      <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>
      <?php 
      if($get['section_type']==1) 
       {?>
        Purchase No.</u>
       <?php }
        ?>
     <?php if($get['section_type']==2) 
       {?>
        Return No.</u>
       <?php }
        ?></div>
      <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>
       <?php 
       if($get['section_type']==1) 
       {?>
        Purchase Date</u>
       <?php }
        ?>
         <?php 
       if($get['section_type']==1) 
       {?>
        Return Date</u>
       <?php }
        ?>

      </u></div>
      <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Vendor Name</u></div>

      <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;"><u>Net Amount</u></div>

      <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;"><u>Paid Amount</u></div>
     
      <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:right;"><u>Balance</u></div>
    </div> 

    <div style="float:left;width:100%;border:1px solid #111;border-top:none;font-size:13px;"> 
 
   
  <?php if(!empty($branch_inventory_list)) { ?>

    <?php 

    $i = 1;   
  
      foreach($branch_inventory_list as $branchs)
      {   
     
      ?>    
      <div style="float:left; width: 100%">
      <div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $i; ?></div>
      <div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->purchase_no; ?></div>
      <div style="float:left;width:20%;padding:1px 4px;"><?php echo date('d-m-Y',strtotime($branchs->purchase_date));?></div>
      <div style="float:left;width:20%;padding:1px 4px;"><?php echo $branchs->name; ?></div>
      <div style="float:left;width:10%;padding:1px 4px;text-align:right;"><?php echo $branchs->net_amount;  //$pay_mode[$branchs->pay_mode]; ?></div>
      <div style="float:left;width:10%;padding:1px 4px;text-align:right;"><?php echo $branchs->paid_amount;  //$pay_mode[$branchs->pay_mode]; ?></div>
      <div style="float:left;width:10%;padding:1px 4px;text-align:right;"><?php echo $branchs->balance;  //$pay_mode[$branchs->pay_mode]; ?></div>

      </div>    



      <?php $i++; } 

      } ?>  

    </div>
        
  </page>

</body>
</html>